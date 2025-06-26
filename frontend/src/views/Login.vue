<template>
	<div class="min-h-screen bg-gray-50 flex">
		<!-- Left side - Form -->
		<div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
			<div class="mx-auto w-full max-w-sm lg:w-96">
				<!-- Header -->
				<div>
					<h2 class="mt-6 text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
					<p class="mt-2 text-sm text-gray-600">
						Or
						<router-link
							to="/register"
							class="font-medium text-indigo-600 hover:text-indigo-500">
							create a new account
						</router-link>
					</p>
				</div>

				<!-- Form -->
				<div class="mt-8">
					<form
						@submit.prevent="handleLogin"
						class="space-y-6">
						<!-- Email Field -->
						<div>
							<label
								for="email"
								class="block text-sm font-medium text-gray-700">
								Email address
							</label>
							<div class="mt-1">
								<input
									id="email"
									v-model="form.email"
									type="email"
									autocomplete="email"
									class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
									:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.email }"
									placeholder="Enter your email"
									required
									@input="errors.email = null" />
								<p
									v-if="errors.email"
									class="mt-2 text-sm text-red-600">
									{{ errors.email }}
								</p>
							</div>
						</div>

						<!-- Password Field -->
						<div class="space-y-1">
							<label
								for="password"
								class="block text-sm font-medium text-gray-700">
								Password
							</label>
							<div class="mt-1">
								<input
									id="password"
									v-model="form.password"
									type="password"
									autocomplete="current-password"
									class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
									:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.password }"
									placeholder="Enter your password"
									required
									@input="errors.password = null" />
								<p
									v-if="errors.password"
									class="mt-2 text-sm text-red-600">
									{{ errors.password }}
								</p>
							</div>
						</div>

						<!-- Remember me and Forgot password -->
						<div class="flex items-center justify-between">
							<div class="flex items-center">
								<input
									id="remember-me"
									name="remember-me"
									type="checkbox"
									class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
								<label
									for="remember-me"
									class="ml-2 block text-sm text-gray-900">
									Remember me
								</label>
							</div>

							<div class="text-sm">
								<a
									href="#"
									class="font-medium text-indigo-600 hover:text-indigo-500">
									Forgot your password?
								</a>
							</div>
						</div>

						<!-- Submit Button -->
						<div>
							<button
								type="submit"
								class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
								:disabled="loading">
								<svg
									v-if="loading"
									class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24">
									<circle
										class="opacity-25"
										cx="12"
										cy="12"
										r="10"
										stroke="currentColor"
										stroke-width="4"></circle>
									<path
										class="opacity-75"
										fill="currentColor"
										d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
								</svg>
								<span v-if="!loading">Sign in</span>
								<span v-else>Signing in...</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Right side - Image/Branding -->
		<div class="hidden lg:block relative w-0 flex-1">
			<div class="absolute inset-0 h-full w-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center">
				<div class="text-center text-white">
					<div class="mb-8">
						<svg
							class="mx-auto h-24 w-24"
							fill="currentColor"
							viewBox="0 0 24 24">
							<path
								d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
						</svg>
					</div>
					<h2 class="text-4xl font-bold mb-4">MyDictionary</h2>
					<p class="text-xl text-indigo-200 max-w-md">
						Your personal dictionary to explore, learn, and save your favorite words.
					</p>
					<div class="mt-8 space-y-2">
						<div class="flex items-center justify-center text-indigo-200">
							<svg
								class="h-5 w-5 mr-2"
								fill="currentColor"
								viewBox="0 0 20 20">
								<path
									fill-rule="evenodd"
									d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
									clip-rule="evenodd" />
							</svg>
							Search millions of words
						</div>
						<div class="flex items-center justify-center text-indigo-200">
							<svg
								class="h-5 w-5 mr-2"
								fill="currentColor"
								viewBox="0 0 20 20">
								<path
									fill-rule="evenodd"
									d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
									clip-rule="evenodd" />
							</svg>
							Save your favorites
						</div>
						<div class="flex items-center justify-center text-indigo-200">
							<svg
								class="h-5 w-5 mr-2"
								fill="currentColor"
								viewBox="0 0 20 20">
								<path
									fill-rule="evenodd"
									d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
									clip-rule="evenodd" />
							</svg>
							Learn every day
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					email: '',
					password: '',
				},
				errors: {
					email: null,
					password: null,
				},
				loading: false,
			};
		},
		methods: {
			validateForm() {
				let isValid = true;

				// Reset errors
				this.errors = { email: null, password: null };

				// Email validation
				if (!this.form.email.trim()) {
					this.errors.email = 'Email is required';
					isValid = false;
				} else if (!/^\S+@\S+\.\S+$/.test(this.form.email)) {
					this.errors.email = 'Please enter a valid email';
					isValid = false;
				}

				// Password validation
				if (!this.form.password) {
					this.errors.password = 'Password is required';
					isValid = false;
				} else if (this.form.password.length < 6) {
					this.errors.password = 'Password must be at least 6 characters';
					isValid = false;
				}

				return isValid;
			},

			async handleLogin() {
				if (!this.validateForm()) return;

				this.loading = true;

				try {
					await this.$store.dispatch('auth/login', this.form);

					// Redirect to intended route or default
					const redirectTo = this.$route.query.redirect || '/search';
					this.$router.push(redirectTo);
				} catch (error) {
					// Handle API errors
					if (error.response) {
						if (error.response.status === 422) {
							// Handle validation errors
							const apiErrors = error.response.data.errors;
							for (const field in apiErrors) {
								if (this.errors.hasOwnProperty(field)) {
									this.errors[field] = apiErrors[field][0];
								}
							}
						} else {
							// Show other API errors
							this.$toast.error(error.response.data.message || 'Login failed');
						}
					} else {
						// Show network errors
						this.$toast.error('Network error. Please try again.');
						console.error('Login error:', error);
					}
				} finally {
					this.loading = false;
				}
			},
		},
	};
</script>
