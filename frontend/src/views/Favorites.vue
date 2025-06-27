<!-- views/Favorites.vue -->
<template>
	<div>
		<h1 class="text-2xl font-bold text-gray-800 mb-6 px-2 max-w-2xl mx-auto">My Favorites</h1>

		<div
			v-if="loading"
			class="text-center py-8">
			<div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600"></div>
		</div>

		<div
			v-else-if="favorites.length === 0"
			class="text-center py-8 text-gray-500">
			You haven't saved any words yet. Search for words to add them to your favorites.
		</div>

		<div
			v-else
			class="space-y-4 px-2">
			<FavoriteItem
				v-for="favorite in favorites"
				:key="favorite.id"
				:favorite="favorite"
				@update="handleUpdateFavorite"
				@delete="handleDeleteFavorite" />
		</div>
	</div>
</template>

<script>
	import FavoriteItem from '@/components/FavoriteItem.vue';

	export default {
		components: {
			FavoriteItem,
		},
		data() {
			return {
				loading: false,
			};
		},
		computed: {
			favorites() {
				return this.$store.state.favorites.favorites.data;
			},
			results() {
				return this.$store.state.favorites.result;
			},
		},
		created() {
			this.loadFavorites();
		},
		methods: {
			async loadFavorites() {
				this.loading = true;
				await this.$store.dispatch('favorites/fetchFavorites');
				this.loading = false;
				if (this.results.success === false) {
					this.$toast.error(this.results.message || 'Failed to load favorites');
				}
			},
			async handleUpdateFavorite({ id, notes }) {
				this.loading = true;
				await this.$store.dispatch('favorites/updateFavorite', { id, notes });
				this.loading = false;
				if (this.results.success) {
					this.loadFavorites();
					this.$toast.success('Note updated successfully!');
				} else {
					this.$toast.error(this.results?.message || 'Failed to update note');
				}
			},
			async handleDeleteFavorite(id) {
				this.loading = true;
				await this.$store.dispatch('favorites/deleteFavorite', id);
				this.loading = false;
				if (this.results.success) {
					this.$toast.success(this.results.message);
					this.loadFavorites();
				} else {
					this.$toast.error(this.results.message || 'Failed to delete favorite');
				}
			},
		},
	};
</script>
