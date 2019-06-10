// pages/goods/info/detail.js
var WxParse = require('../../../wxParse/wxParse.js');
const app = getApp()
var base = require('../../../utils/base.js')

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
		hiddenCover: !0,
		hiddenPlay: !0,
		played: !1,
		tabs: ["课程详情"],
		controls: !0,
		activeIndex: 0,
		sliderOffset: 0,
		sliderLeft: 0,
		video_url: '',
		cIndex: 0,
		form: {
			realname: '',
			mobile: ''
		}
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (e) {
		var t = this;
		t.setData({
			id: e.id ? e.id : 0
		});
		this.Modal = this.selectComponent("#modal");
	},
	getProductInfo: function () {
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('getcourse', t, true).then(function (res) {
			if (res.code == 1) {
				var i = res.data.if_has_buy, r = res.data.goods_price;
				var hiddenPlay = 0 === Number(r) || i ? 0 : 1;
				this.setData({
					info: res.data,
					hiddenPlay: hiddenPlay,
					video_url: res.data.chapter && res.data.chapter.length > 0 && res.data.chapter[this.data.cIndex].chapter_link ? res.data.chapter[this.data.cIndex].chapter_link : ''
				})
				var content = res.data.goods_intro;
				WxParse.wxParse('content', 'html', content, this, 5);
			}
		}.bind(this))
	},
	tabClick: function (e) {
		var t = this, a = wx.createSelectorQuery();
		a.select(".weui-bar__item_on").boundingClientRect(), a.exec(function (a) {
			wx.getSystemInfo({
				success: function (n) {
					t.setData({
						sliderWidth: a[0].width,
						sliderOffset: e.currentTarget.offsetLeft,
						activeIndex: e.currentTarget.id
					});
				}
			});
		});
	},

	playVideo: function () {
		var e = this, t = this, o = this.data.info, i = o.if_has_buy, r = o.goods_price;
		0 === Number(r) || i ? this.goPlay() : e.setData({
			hiddenPlay: !0
		});
	},

	goPlay: function () {
		if (!this.data.video_url) {
			wx.showToast({
				icon: 'none',
				title: '视频加载失败',
			})
			return;
		}
		this.setData({
			played: !0
		})
		var a = wx.createVideoContext("myVideo");
		a.play()
	},

	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow: function () {
		var t = this;
		var a = wx.createSelectorQuery();
		a.select(".weui-bar__item_on").boundingClientRect(), a.exec(function (e) {
			wx.getSystemInfo({
				success: function (a) {
					var n = a.windowWidth / 750;
					t.setData({
						sliderWidth: e[0].width,
						sliderOffset: e[0].left - 20 * n
					});
				}
			});
		}), this.getProductInfo();
	},

	fav: function () {
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('fav', t, false).then(function (res) {
			if (res.code == 1) {
				if (res.data == 'cancel') {
					wx.showToast({
						icon: 'none',
						title: '已取消收藏',
					})
					this.setData({
						'info.is_fav': 0
					})
				} else {
					wx.showToast({
						icon: 'none',
						title: '收藏成功',
					})
					this.setData({
						'info.is_fav': 1
					})
				}
			}
		}.bind(this))
	},
	sign: function () {
		this.Modal.showModal();
	},
	confirmEvent: function () {
		if (!this.data.form.realname) {
			wx.showToast({
				icon: 'none',
				title: '请输入姓名',
			})
			return;
		}
		if (!this.data.form.mobile) {
			wx.showToast({
				icon: 'none',
				title: '请输入手机号',
			})
			return;
		}

		if (!/^1\d{10}$/.test(this.data.form.mobile)) {
			wx.showToast({
				icon: 'none',
				title: '请输入正确手机号',
			})
			return;
		}
		try {
			var t = {}
			t.openid = base.getOpenid();
			t.realname = this.data.form.realname
			t.mobile = this.data.form.mobile
			t.id = this.data.id
			app.HttpService.__request('sign', t, true).then(function (res) {
				if (res.code == 1) {
					this.setData({
						form: {
							realname: '',
							mobile: ''
						},
						'info.if_has_sign': 1
					})

					this.Modal.hideModal();

					wx.showToast({
						title: '报名成功',
					})
				} else {
					wx.showToast({
						title: res.msg
					})
				}
			}.bind(this))
		} catch (e) {

		}
	},
	collectFormId: function (e) {
		console.log(e)
		base.saveFormid(e)
	},
	input1: function (e) {
		this.setData({
			'form.realname': e.detail.value
		})
	},
	input2: function (e) {
		this.setData({
			'form.mobile': e.detail.value
		})
	},
})