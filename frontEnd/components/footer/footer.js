// components/footer/footer.js
var base = require('../../utils/base.js');

Component({
	/**
	 * 组件的属性列表
	 */
	properties: {
		current: {
			type: String,
			value: 'index'
		},
	},

	/**
	 * 组件的初始数据
	 */
	data: {
		// tabBar: base.setTabBar()
	},

	/**
	 * 组件的方法列表
	 */
	methods: {
		switchTab: function (i) {
			this.data.tabBar.current = i
		},
	},
})
