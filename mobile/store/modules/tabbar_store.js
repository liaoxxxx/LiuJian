import app from '../../config/app.json';

export default {
	state: {
		tabBar: Object.assign({
			"color": "#9a9a9a",
			"selectedColor": "#ff793e",
			"borderStyle": "black",
			"backgroundColor": "#f6f6f6",
		}, app.tabBar)
	},
	mutations: {
		setTabBarBadge(state, badge) {
			// console.log(state.tabBar.list)
			state.tabBar.list[badge.index].badge = badge.text;
			uni.setTabBarBadge(badge)
		}
	},
	actions: {

	}
}
