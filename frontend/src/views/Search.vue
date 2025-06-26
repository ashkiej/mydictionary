<!-- views/Search.vue -->
<template>
	<div>
		<div class="mb-8">
			<div class="relative max-w-2xl mx-auto">
				<input
					v-model="searchTerm"
					type="text"
					placeholder="Search for a word..."
					class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
					@keyup.enter="searchWord" />
				<button
					@click="searchWord"
					class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700 disabled:opacity-50"
					:disabled="loading">
					{{ loading ? 'Searching...' : 'Search' }}
				</button>
			</div>
		</div>

		<div
			v-if="loading"
			class="text-center py-8">
			<div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600"></div>
			<p class="mt-2 text-gray-600">Searching for "{{ searchTerm }}"...</p>
		</div>

		<!-- Display single word result -->
		<div
			v-else-if="searchResults && Object.keys(searchResults).length > 0"
			class="flex justify-center">
			<WordCard
				:word-data="searchResults"
				@save="handleSaveWord" />
		</div>

		<!-- No results found -->
		<div
			v-else-if="searchAttempted && (!searchResults || Object.keys(searchResults).length === 0)"
			class="text-center py-8">
			<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded max-w-md mx-auto">
				<p class="font-medium">No results found</p>
				<p class="text-sm">No definition found for "{{ searchTerm }}". Please check the spelling and try again.</p>
			</div>
		</div>

		<!-- Initial state -->
		<div
			v-else
			class="text-center py-12">
			<div class="max-w-md mx-auto">
				<div class="text-6xl mb-4">📚</div>
				<h2 class="text-xl font-semibold text-gray-700 mb-2">Dictionary Search</h2>
				<p class="text-gray-500">Enter a word above to see its definition, pronunciation, and examples.</p>
			</div>
		</div>

		<!-- Error handling -->
		<div
			v-if="error"
			class="mt-4 flex justify-center">
			<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded max-w-md">
				<p class="font-medium">Search Error</p>
				<p class="text-sm">{{ error }}</p>
			</div>
		</div>
	</div>
</template>

<script>
	import WordCard from '@/components/WordCard.vue';

	export default {
		components: {
			WordCard,
		},
		data() {
			return {
				searchTerm: '',
				searchResults: null,
				loading: false,
				searchAttempted: false,
				error: null,
			};
		},
		computed: {
			results() {
				return this.$store.state.favorites.result;
			},
		},
		methods: {
			async searchWord() {
				if (!this.searchTerm.trim()) return;

				this.loading = true;
				this.searchAttempted = true;
				this.error = null;
				this.searchResults = null;

				try {
					const response = await this.$store.dispatch('dictionary/searchWord', this.searchTerm);

					// Handle the response data
					if (response && response.data) {
						this.searchResults = response.data;
					} else {
						this.searchResults = null;
					}

					console.log('Search results:', this.searchResults);
				} catch (error) {
					console.error('Search error:', error);

					// Handle different types of errors
					if (error.response?.status === 404) {
						this.error = `No definition found for "${this.searchTerm}"`;
					} else if (error.response?.data?.message) {
						this.error = error.response.data.message;
					} else {
						this.error = 'Search failed. Please try again.';
					}

					this.searchResults = null;
					this.$toast.error(this.error);
				} finally {
					this.loading = false;
				}
			},

			async handleSaveWord(wordData) {
				await this.$store.dispatch('favorites/addFavorite', wordData);
				if (this.results.success) {
					this.$toast.success(`"${wordData.word}" saved to favorites!`);
				} else {
					if (this.results?.message === 'Word already in favorites') {
						this.$toast.warning(`"${wordData.word}" is already in your favorites`);
					} else {
						this.$toast.error(this.results.message || 'Failed to save word');
					}
				}
			},

			// Optional: Clear search results
			clearSearch() {
				this.searchTerm = '';
				this.searchResults = null;
				this.searchAttempted = false;
				this.error = null;
			},
		},

		// Optional: Auto-search when component receives a word prop
		props: {
			initialWord: {
				type: String,
				default: '',
			},
		},

		mounted() {
			// If there's an initial word, search for it
			if (this.initialWord) {
				this.searchTerm = this.initialWord;
				this.searchWord();
			}
		},
	};
</script>
