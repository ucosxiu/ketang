import ServiceBase from 'ServiceBase'
var n = require("../oss/crypto.js").Crypto

class Service extends ServiceBase {
	constructor() {
		super()
		this.$$prefix = ''
		this.$$path = {
			saveformid: '/index.php/api/member/saveformid',
			getOpenid: '/index.php/api/data/getopenid',
			getads: '/index.php/api/data/getads',
			getarticleclass: '/index.php/api/data/getarticleclass',
			getindex: '/index.php/api/data/getindex',
			getcourse: '/index.php/api/course/detail',
			fav: '/index.php/api/course/fav',
			buy: '/index.php/api/course/buy',
			pay: '/index.php/api/course/pay',
			sign: '/index.php/api/course/sign',
			goodslog: '/index.php/api/log/addlog',
			goodsjoin: '/index.php/api/course/join',
			joinlist: '/index.php/api/course/joinlist',

			delJoin: '/index.php/api/course/delJoin',
			
			getarticles: '/index.php/api/article/getlist',
			getarticle: '/index.php/api/article/detail',

			favlist: '/index.php/api/fav/getlist',
			orderlist: '/index.php/api/order/getlist',

			teacherinfo: '/index.php/api/teacher/info',
			teacherlist: '/index.php/api/teacher/getlist',
			
			getOrderList: '/index.php/api/order/getlist',
			getOrder: '/index.php/api/order/detail',
			repayOrder: '/index.php/api/order/repay',
			cancelOrder: '/index.php/api/order/cancel',
			deleteOrder: '/index.php/api/order/delete',
			getOrderInfo: '/index.php/api/order/detail',
		}
	}

	__request(key, _params = {}, load = true, type = 'post') {
		if (this.$$path[key]) {
			this._selLoad(load)
			let params = this.makeData(_params)
			return this.postRequest(this.$$path[key], params)
		}
	}

	makeData(e) {
		var a = new Date().getTime(), o = this.getNonceStr(), r = {
			noncestr: o,
			timestamp: a
		}, u = {};
		for (var s in r) u[s] = r[s];
		for (var s in e) void 0 !== e[s] && (u[s] = e[s]);

		var c = this.t(u), p = n.MD5(c);
		p += "17378a5fe415fc1abf05b25f606392d7";
		var m = getApp().Tools.encryptData(p);
		// var m = p;
		e.noncestr = o;
		e.timestamp = a;
		e.signature = m
		return e;
	}

	e(e) {
		return e = e.replace(/!/g, "%21"), e = e.replace(/~/g, "%7E"), e = e.replace(/\*/g, "%2A"),
			e = e.replace(/'/g, "%27"), e = e.replace(/\(/g, "%28"), e = e.replace(/\)/g, "%29");
	}

	t(t) {
		var n = [], a = Object.keys(t).sort();
		console.log("newkey=" + t);
		for (var r = {}, u = 0; u < a.length; u++) "address" == a[u] || "content" == a[u] || "nickname" == a[u] || "oss_img" == a[u] || "avatar" == a[u] || "back_img" == a[u] || "font_color" == a[u] ? r[a[u]] = this.e(encodeURIComponent(t[a[u]])) : "location" == a[u] ? r[a[u]] = encodeURIComponent(JSON.stringify(t[a[u]])) : r[a[u]] = t[a[u]],
			n.push(a[u] + "=" + r[a[u]]);
		return n.join("&").toUpperCase();
	}


	getNonceStr(length) {
		length = length ? length : 32;
		var chars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
		var str = "";
		for (var i = 0; i < length; i++) {
	
			// $str += substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			str += chars[Math.floor(Math.random() * chars.length)]
		}
		return str;
	}

	//上传图片 
	// param img 图片；
	fileupload(key = 'upload', callback) {
		var self = this;
		wx.chooseImage({
			success(res) {
				const tempFilePaths = res.tempFilePaths
				wx.uploadFile({
					url: getApp().Config.basePath + self.$$path[key], // 仅为示例，非真实的接口地址
					filePath: tempFilePaths[0],
					name: 'file',
					formData: {
						user: 'test'
					},
					success(res) {
						if (res.statusCode == 200) {
							callback(JSON.parse(res.data))
						} else {
							wx.showToast({
								title: '上传失败',
							})
						}
					}
				})
			}
		})
	}
}

export default Service