// pages/order/list.js
const app = getApp()
var base = require('../../utils/base.js')
var _this = null;

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
		'type': '',
		lists: [],
		page: {
			total: 0,
			current_page: 1,
			last_page: 1,
			loading: !1,
			more: !1,
			isload: !1
		},
		hasShow: 0,
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (options) {
		_this = this;
		this.setData({
			hasShow: 0
		})
	},

	onShow: function () {
		if (this.data.hasShow) {
			return;
		}

		this.setData({
			hasShow: 1
		})
		this.getOrderList(true);
	},

	/**
		 * 生命周期函数--监听页面初次渲染完成
		 */
	getOrderList: function (flag) {
		if (flag) {
			this.setData({
				lists: [],
				page: {
					total: 0,
					current_page: 1,
					last_page: 1,
					loading: 1,
					more: !1,
					isload: !1
				}
			})
		}
		var t = {};
		t.openid = base.getOpenid();
		this.data.type && (t.type = this.data.type);
		t.page = this.data.page.current_page
		app.HttpService.__request('getOrderList', t, flag).then(function (res) {
			if (res.code == 1) {
				var lists = res.data.data
				if (this.data.page.isload) {
					this.setData({
						lists: this.data.lists.concat(res.data.data),
						'page.loading': !1,
					})
				} else {
					var more = !0;
					if (res.data.last_page == this.data.page.current_page) {
						more = !1
					}
					this.setData({
						lists: lists,
						'page.total': res.data.total,
						'page.last_page': res.data.last_page,
						'page.loading': !1,
						'page.more': more,
						'page.isload': !0
					})
				}

			}
		}.bind(this))
	},

	/**
	 * 页面上拉触底事件的处理函数
	 */
	onReachBottom: function () {
		return;
		if (this.data.page.loading || !this.data.page.more) {
			return false;
		}

		if (this.data.page.current_page >= this.data.page.last_page) {
			this.setData({
				'page.loading': !1,
				'page.more': !1
			})
			return false;
		}

		this.setData({
			'page.loading': !0,
			'page.current_page': this.data.page.current_page + 1
		})

		this.getOrderList(false);
	},
	onPullDownRefresh: function () {
		this.getOrderList(true);
	},
	filter: function (e) {
		var type = e.currentTarget.dataset.type;
		this.setData({
			type: type,
		})
		this.getOrderList(true)
	},
	order_operable: function (e) {
		var id = e.currentTarget.dataset.id;
		var op = e.currentTarget.dataset.op;
		switch (op) {
			case 'delete':
				this.delete(id)
				break;
			case 'repay':
				this.repay(id);
				break;
			case 'cancel':
				this.cancel(id)
				break;
		}
	},
	evaluation: function (id) {
		wx.navigateTo({
			url: '/pages/order/evaluation?id=' + id
		})
	},
	repay: function (id) {
		var tt = {}
		tt.openid = base.getOpenid();
		tt.id = id;
		app.HttpService.__request('repayOrder', tt, true).then(function (t) {
			if (t.code == 1) {
				var data = t.data;
				data.id = id;
				wx.showModal({
					content: '模拟支付',
					confirmText: '去支付',
					success(res) {
						if (res.confirm) {
							_this.success(data)
						}
					}
				})
				return;
				var o = t.result;
				"string" == typeof t.result && (o = JSON.parse(t.result)), wx.requestPayment({
					timeStamp: o.timeStamp,
					nonceStr: o.nonceStr,
					package: o.package,
					signType: "MD5",
					paySign: o.paySign,
					success: function (e) {
						wx.showToast({
							title: '支付成功',
						})
						_this.getOrderList(true);
					},
					fail: function (t) {
						wx.showToast({
							title: '支付失败',
						})
					}
				});
			} else if (t.code == 2) {
				wx.showToast({
					title: '支付失败',
				})
			} else {
				wx.showToast({
					title: t.msg + ''
				})
			}
		})
	},
	success: function (data) {
		var t = {}
		t.openid = base.getOpenid();
		t.id = data.id
		t.pay_sn = data.pay_sn
		app.HttpService.__request('pay', t, true).then(function (res) {
			if (res.code == 1) {
				_this.getOrderList(true);
			} else {
				wx.showToast({
					icon: 'none',
					title: '购买失败',
				})
			}
		}.bind(this))
	},
	delete: function (id) {
		wx.showModal({
			content: '确认删除订单？',
			success(res) {
				if (!res.confirm) {
					return;
				}
				var t = {}
				t.openid = base.getOpenid();
				t.id = id
				app.HttpService.__request('deleteOrder', t, true).then(function (res) {
					if (res.code) {
						wx.showToast({
							title: '删除订单成功',
						})
						_this.getOrderList(true);
					} else {
						wx.showToast({
							title: res.msg,
						})
					}
				})
			}
		})
	},
	cancel: function (id) {
		wx.showModal({
			content: '确认取消订单？',
			success(res) {
				if (!res.confirm) {
					return;
				}
				var t = {}
				t.openid = base.getOpenid();
				t.id = id;
				app.HttpService.__request('cancelOrder', t, true).then(function (res) {
					if (res.code) {
						wx.showToast({
							title: '取消订单成功',
						})
						_this.getOrderList(true);
					} else {
						wx.showToast({
							title: res.msg,
						})
					}
				})
			}
		})
	},
	recieve: function (id) {
		wx.showModal({
			content: '是否确认收货？',
			success(res) {
				if (!res.confirm) {
					return;
				}
				var t = {}
				t.openid = base.getOpenid();
				t.id = id;
				app.HttpService.__request('recieveOrder', t, true).then(function (res) {
					if (res.code) {
						wx.showToast({
							title: '确认收货成功'
						})
						setTimeout(function () {
							_this.getOrderList(true);
						}, 1500)

					} else {
						wx.showToast({
							title: res.msg,
						})
					}
				})
			}
		})
	}
})