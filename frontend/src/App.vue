<template>
	<div class="min-h-screen bg-gray-50">
		<template v-if="!['Login', 'Register'].includes($route.name)">
			<NavBar />
		</template>
		<main class="container mx-auto">
			<router-view />
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
	};
</script>
