// pages/order/index.js
const app = getApp()
var base = require('../../utils/base.js')
import util from '../../utils/util.js'
var _this = null;

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (e) {
		var id = e.id;
		if (id) {
			this.setData({
				order_id: id
			})
		} else {
			wx.navigateBack({})
		}

		this.getOrderInfo(true);
	},

	getOrderInfo: function (flag) {
		var t = {};
		t.openid = base.getOpenid();
		t.id = this.data.order_id
		app.HttpService.__request('getOrderInfo', t, flag).then(function (res) {
			if (res.code == 1) {
				this.setData({
					orderinfo: res.data
				})
			} else {
				wx.showToast({
					title: res.msg
				})
			}
		}.bind(this))
	},


	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow: function () {

	},
})