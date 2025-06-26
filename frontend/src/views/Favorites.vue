<!-- views/Favorites.vue -->
<template>
	<div>
		<h1 class="text-2xl font-bold text-gray-800 mb-6">My Favorites</h1>

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
			class="space-y-4">
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
				return this.$store.state.favorites.favorites;
			},
		},
		created() {
			this.loadFavorites();
		},
		methods: {
			async loadFavorites() {
				this.loading = true;
				try {
					await this.$store.dispatch('favorites/fetchFavorites');
				} catch (error) {
					this.$toast.error(error.response?.data?.message || 'Failed to load favorites');
				} finally {
					this.loading = false;
				}
			},
			async handleUpdateFavorite({ id, notes }) {
				try {
					await this.$store.dispatch('favorites/updateFavorite', { id, notes });
					this.$toast.success('Note updated successfully!');
				} catch (error) {
					this.$toast.error(error.response?.data?.message || 'Failed to update note');
				}
			},
			async handleDeleteFavorite(id) {
				try {
					await this.$store.dispatch('favorites/deleteFavorite', id);
					this.$toast.success('Word removed from favorites');
				} catch (error) {
					this.$toast.error(error.response?.data?.message || 'Failed to delete favorite');
				}
			},
		},
	};
</script>
