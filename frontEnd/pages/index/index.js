//index.js
//获取应用实例
const app = getApp()
var base = require('../../utils/base.js')

Page({
	data: {
		host: app.Config.fileBasePath,
		indicatorDots: true,
		autoplay: true,
		interval: 5000,
		duration: 1000,
		ads: [],
		navs: [],
		hots: [],
		recommends: []

	},
	onLoad: function () {
		this.getIndex()
	},
	getIndex: function() {
		var t = {}
		t.model = 'index_ad'
		app.HttpService.__request('getindex', t, false).then(function (res) {
			if (res.code == 1) {
				this.setData({
					ads: res.data.ads,
					navs: res.data.navs,
					hots: res.data.hots,
					recommends: res.data.recommends
				})
			}
			wx.stopPullDownRefresh();
		}.bind(this))
	},
	collectFormId: function(e) {
		console.log(e)
		base.saveFormid(e)
	},
	onPullDownRefresh: function () {
		wx.stopPullDownRefresh();
		this.setData({
			ads: [],
			navs: [],
			hots: [],
			recommends: []
		}), this.getIndex();
		var t = this
		setTimeout(function () {// 这里写刷新要调用的函数，比如：
			wx.stopPullDownRefresh();
		}, 100);
	}
})
