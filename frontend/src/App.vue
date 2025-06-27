<template>
	<div class="min-h-screen bg-gray-50">
		<template v-if="!['Login', 'Register'].includes($route.name)">
			<NavBar />
		</template>
		<div
			v-if="loading"
			class="text-center py-8 fixed inset-0 bg-gray-100 bg-opacity-60 z-50 flex items-center justify-center">
			<div class="inline-block animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-blue-600"></div>
		</div>
		<main class="container mx-auto">
			<router-view @loading="setLoading" />
		</main>
		<NotificationToast />
	</div>
</template>

<script>
	import NavBar from './components/NavBar.vue';
	import NotificationToast from './components/NotificationToast.vue';

	export default {
		components: {
			NavBar,
			NotificationToast,
		},
		data() {
			return {
				loading: false,
			};
		},
		created() {
			this.$router.beforeEach((to, from, next) => {
				this.loading = true;
				// Add a small delay before navigating to simulate loading
				setTimeout(() => {
					next();
				}, 100);
				return;
				next();
			});
			this.$router.afterEach(() => {
				this.loading = false;
			});
		},
		methods: {
			setLoading(val) {
				this.loading = val;
			},
		},
	};
</script>
