// pages/teacher/info.js
const app = getApp()
var base = require('../../utils/base.js')

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
		tabs: ["动态", "课程"],
		activeIndex: 0,
		sliderOffset: 0,
		sliderLeft: 0,
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (e) {
		var t = this;
		t.setData({
			id: e.id ? e.id : 0
		});

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
		}), this.getInfo();
	},

	getInfo: function () {
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('teacherinfo', t, true).then(function (res) {
			if (res.code == 1) {
				this.setData({
					info: res.data
				})
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

	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow: function () {

	}
})