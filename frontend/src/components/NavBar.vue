<template>
	<nav class="bg-white shadow-lg py-4 mb-10">
		<div class="container mx-auto px-4 flex justify-between items-center">
			<router-link
				to="/"
				class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition duration-300 ease-in-out"
				>MyDictionary</router-link
			>
			<div class="flex items-center space-x-6">
				<template v-if="isAuthenticated">
					<router-link
						to="/search"
						class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
						>Search</router-link
					>
					<router-link
						to="/favorites"
						class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
						>Favorites</router-link
					>
					<button
						@click="handleLogout"
						class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out">
						Logout
					</button>
				</template>
				<template v-else>
					<router-link
						to="/login"
						class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
						>Login</router-link
					>
					<router-link
						to="/register"
						class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
						>Register</router-link
					>
				</template>
			</div>
		</div>
	</nav>
</template>

<script>
	import { mapGetters } from 'vuex';

	export default {
		computed: {
			...mapGetters('auth', ['isAuthenticated']),
		},
		methods: {
			async handleLogout() {
				try {
					await this.$store.dispatch('auth/logout');
					this.$router.push('/login');
				} catch (error) {
					this.$toast.error('Logout failed');
				}
			},
		},
	};
</script>
