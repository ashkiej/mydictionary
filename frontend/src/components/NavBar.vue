<template>
	<nav class="bg-white shadow-lg py-4 mb-10">
		<div class="container mx-auto px-4 flex justify-between items-center">
			<router-link
				to="/"
				class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition duration-300 ease-in-out"
				>MyDictionary</router-link
			>
			<!-- Desktop menu -->
			<div class="hidden md:flex items-center space-x-6">
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
			<!-- Mobile burger menu -->
			<div class="md:hidden flex items-center">
				<button
					@click="toggleMenu"
					class="focus:outline-none">
					<svg
						class="w-7 h-7 text-gray-800"
						fill="none"
						stroke="currentColor"
						viewBox="0 0 24 24">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M4 6h16M4 12h16M4 18h16" />
					</svg>
				</button>
				<!-- Dropdown menu -->
				<div
					v-if="isMenuOpen"
					class="absolute top-16 right-4 z-50 bg-white shadow-lg py-2 px-4 min-w-[140px] flex flex-col space-y-2">
					<template v-if="isAuthenticated">
						<router-link
							to="/search"
							class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
							@click.native="closeMenu"
							>Search</router-link
						>
						<router-link
							to="/favorites"
							class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
							@click.native="closeMenu"
							>Favorites</router-link
						>
						<button
							@click="handleLogoutAndClose"
							class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out text-left">
							Logout
						</button>
					</template>
					<template v-else>
						<router-link
							to="/login"
							class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
							@click.native="closeMenu"
							>Login</router-link
						>
						<router-link
							to="/register"
							class="text-gray-800 hover:text-gray-900 transition duration-300 ease-in-out"
							@click.native="closeMenu"
							>Register</router-link
						>
					</template>
				</div>
			</div>
		</div>
	</nav>
</template>

<script>
	import { mapGetters } from 'vuex';

	export default {
		data() {
			return {
				isMenuOpen: false,
			};
		},
		computed: {
			...mapGetters('auth', ['isAuthenticated']),
		},
		methods: {
			toggleMenu() {
				this.isMenuOpen = !this.isMenuOpen;
			},
			closeMenu() {
				this.isMenuOpen = false;
			},
			async handleLogout() {
				try {
					await this.$store.dispatch('auth/logout');
					this.$router.push('/login');
				} catch (error) {
					this.$toast.error('Logout failed');
				}
			},
			async handleLogoutAndClose() {
				this.isMenuOpen = false;
				await this.handleLogout();
			},
		},
	};
</script>
