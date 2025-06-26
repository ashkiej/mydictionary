<template>
	<div class="min-h-screen">
		<NavBar />
		<main class="container">
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
