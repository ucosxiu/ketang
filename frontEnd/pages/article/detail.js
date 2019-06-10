// pages/article/detail.js
var WxParse = require('../../wxParse/wxParse.js');
const app = getApp()
var base = require('../../utils/base.js');

Page({

	/**
	 * 页面的初始数据
	 */
	data: {

	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (e) {
		this.setData({
			id: e.id
		})

		this.getDetail(true);
	},
	
	getDetail: function (flag) {
		var t = {}
		t.openid = base.getOpenid();
		t.id = this.data.id
		app.HttpService.__request('getarticle', t, flag).then(function (res) {
			if (res.code == 1) {
				this.setData({
					article: res.data,
				})
				var content = res.data.article_content;
				WxParse.wxParse('content', 'html', content, this, 5);
			}
		}.bind(this))
	},

	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow: function () {

	}
})