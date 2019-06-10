// pages/teacher/list.js
const app = getApp()
var base = require('../../utils/base.js')

Page({

	/**
	 * 页面的初始数据
	 */
	data: {
		host: app.Config.fileBasePath,
		lists: [],
		page: {
			total: 0,
			current_page: 1,
			last_page: 1,
			loading: !1,
			more: !1,
			isload: !1
		}
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad: function (options) {
	},

	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow: function () {
		this.init(true)
	},

	init: function (flag) {
		this.setData({
			lists: [],
			page: {
				total: 0,
				current_page: 1,
				last_page: 1,
				loading: !1,
				more: !1,
				isload: !1
			}
		})

		this.getList(flag);
	},

	getList: function (flag) {
		this.setData({
			'page.loading': !0,
		})

		var t = {};
		t.openid = base.getOpenid();
		t.page = this.data.page.current_page

		app.HttpService.__request('teacherlist', t, flag).then(function (res) {
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
	 * 页面相关事件处理函数--监听用户下拉动作
	 */
	onPullDownRefresh: function () {
		this.init(false)
		setTimeout(function () {// 这里写刷新要调用的函数，比如：
			wx.stopPullDownRefresh();
		}, 100);
	},

	/**
	 * 页面上拉触底事件的处理函数
	 */
	onReachBottom: function () {
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

		this.getList(false);
	}
})