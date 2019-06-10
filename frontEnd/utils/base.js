const app = getApp();

module.exports = {
	getTabBar() {
		return [
			{
				'key': 'index',
				'url': '/pages/index/index',
				'text': '首页',
				'icon': 'iconshouye',
			},
			{
				'key': 'company',
				'url': '/pages/company/index',
				'text': '公司',
				'icon': 'icongongsixinxi',
			},
			{
				'key': 'resume',
				'url': '/pages/resume/index',
				'text': '简历',
				'icon': 'iconxinjianjianli',
			},
			{
				'key': 'member',
				'url': '/pages/member/index',
				'text': '我的',
				'icon': 'iconwode',
			}
		]
	},
	setTabBar: function () {
		console.log('sss');
		// wx.hideTabBar({})
		return {
			"color": "#555555",
			"selectedColor": "#39bfb2",
			"borderStyle": "white",
			'current': 'index',
			list: this.getTabBar()
		}
	},
	checkUserInfo: function (cb) {
		if (!getApp().globalData.userInfo) {
			var openid = this.getOpenid();
			if (openid) {
				this.getUserinfo({
					openid: openid
				}).then(function (res) {
					this.dealUserInfo(res, cb)
				}.bind(this));
			} else {
				this.getOpenidSync().then(function (openid) {
					this.getUserinfo({
						openid: openid
					}).then(function (res) {
						this.dealUserInfo(res, cb)
					}.bind(this));
				}.bind(this))
			}
		} else {
			if (cb && cb instanceof Function) {
				cb()
			}
		}
	},


	dealUserInfo: function (res, cb) {
		if (res.code == 1) {
			wx.setStorageSync('userinfo', res.data);
			wx.setStorageSync('openid', res.data.openid);
			getApp().globalData.userInfo = res.data;
			if (cb && cb instanceof Function) {
				cb()
			}
		} else if (res.code == 100) {
			// 未绑定
			wx.navigateTo({
				url: '/pages/account/login'
			})
		}
	},
	getOpenidSync: function () {
		return new Promise(function (e, f) {
			this.getCode().then(function (code) {
				getApp().HttpService.__request('getOpenid', { code: code }, false).then(function (res) {
					if (res.code) {
						wx.setStorageSync('openid', res.data.openid);
						e(res.data.openid)
					}
				})
			}.bind(this))
		}.bind(this));
	},
	checkAuthLogin: function () {
		try {
			var openid = wx.getStorageSync('openid');
			if (openid) {
				return true;
			}
		} catch (e) {

		}
		return false;
	},
	getCode: function () {
		return new Promise(function (e, t) {
			wx.login({
				success: function (n) {
					n.code ? e(n.code) : t();
				}
			});
		});
	},
	login: function () {
		return new Promise(function (e, t) {
			wx.login({
				success: function (n) {
					n.code ? e(n.code) : t();
				}
			});
		});

	},
	getUserinfo: function (t) {
		return new Promise(function (e, f) {
			getApp().HttpService.__request('getUserinfo', t, false).then(function (res) {
				if (res.code) {
					e(res)
				}
			}.bind(this))
		}.bind(this));
	},
	getOpenid: function () {
		try {
			var openid = wx.getStorageSync('openid');
			if (openid) {
				return openid;
			}
		} catch (e) {

		}
		return false;
	},
	getStoreageUserinfo: function () {
		try {
			var userinfo = wx.getStorageSync('userinfo');
			if (userinfo) {
				return userinfo;
			}
		} catch (e) {

		}
		return false;
	},
	saveFormid: function (e) {
		e.detail && e.detail.formId && e.detail.formId != 'the formId is a mock one' && setTimeout(function () {
			var p = {
				formid: e.detail.formId,
				openid: this.getOpenid()
			}
			getApp().HttpService.__request('saveformid', p, false).then(function (res) {

			}.bind(this))
		}.bind(this), 100)
	}
}