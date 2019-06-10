// pages/goods/video/detail.js
const app = getApp()
var base = require('../../../utils/base.js')
var WxParse = require('../../../wxParse/wxParse.js');

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
		hiddenCover: !0,
		hiddenPlay: !0,
		played: !1,
		tabs: ["章节目录", "课程详情"],
		controls: !0,
		activeIndex: 0,
		sliderOffset: 0,
		sliderLeft: 0,
		video_url: '',
		cIndex: 0
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (e) {
		var t = this;
		t.setData({
			id: e.id ? e.id : 0
		});
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
		this.log();
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

	log: function () {
		//添加学习日志
		if (this.data.info.chapter[this.data.cIndex]) {
			var chapter = this.data.info.chapter[this.data.cIndex];
			var t = {}
			t.openid = base.getOpenid();
			t.goods_id = this.data.id
			t.chapter_id = chapter.chapter_id;
			app.HttpService.__request('goodslog', t, false).then(function (res) {
				if (res.code == 1) {
				}
			})
		}
	},

	join: function() {
		var _t = this;
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('goodsjoin', t, true).then(function (res) {
			if (res.code == 1) {
				wx.showToast({
					icon: 'none',
					title: '加入成功'
				})
				this.setData({
					'info.if_has_join': 1
				})
			} else {
				wx.showToast({
					icon: 'none',
					title: '加入失败',
				})
			}
		}.bind(this))
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
	onChapterPlay: function (e) {
		var a = e.currentTarget.dataset.index, n = this.data.info.chapter, o = void 0, i = void 0;
		if (a == this.data.cIndex && this.data.played) {
			return;
		}

		if (!this.data.info.chapter[a] || !this.data.info.chapter[a].chapter_link) {
			return;
		}
		wx.createVideoContext("myVideo").stop();
		this.setData({
			cIndex: a,
			played: !0,
			video_url: this.data.info.chapter[a].chapter_link
		})
		this.log()
		wx.createVideoContext("myVideo").play();
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
	buy: function () {
		var _t = this;
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('buy', t, true).then(function (res) {
			var data = res.data;
			if (res.code == 1) {
				wx.showModal({
					content: '模拟支付',
					confirmText: '去支付',
					success(res) {
						if (res.confirm) {
							_t.success(data)
						}
					}
				})
			}
		}.bind(this))
	},
	success: function (data) {
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		t.pay_sn = data.pay_sn
		app.HttpService.__request('pay', t, true).then(function (res) {
			if (res.code == 1) {
				wx.showToast({
					icon: 'none',
					title: '购买成功',
				})
				this.setData({
					'info.if_has_buy': 1,
					hiddenPlay: !1,
				})
			} else {
				wx.showToast({
					icon: 'none',
					title: '购买失败',
				})
			}
		}.bind(this))
	},
	collectFormId: function (e) {
		console.log(e)
		base.saveFormid(e)
	},
})