<!-- components/FavoriteItem.vue -->
<template>
	<div class="bg-white rounded-lg shadow p-4">
		<div class="flex justify-between items-start">
			<div>
				<h3 class="text-xl font-bold text-gray-800">{{ favorite.word }}</h3>
				<p
					v-if="favorite.phonetic"
					class="text-gray-600 italic">
					{{ favorite.phonetic }}
				</p>
			</div>
			<button
				@click="handleDelete"
				class="text-red-600 hover:text-red-800">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					class="h-5 w-5"
					fill="none"
					viewBox="0 0 24 24"
					stroke="currentColor">
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						stroke-width="2"
						d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
				</svg>
			</button>
		</div>

		<div class="mt-3">
			<h4 class="text-sm font-medium text-gray-500">Definitions:</h4>
			<ul class="mt-1 space-y-1">
				<li
					v-for="(def, index) in favorite.definitions"
					:key="index"
					class="ml-4">
					<p class="text-gray-800">
						<span class="font-medium">{{ index + 1 }}.</span> {{ def }}
					</p>
				</li>
			</ul>
		</div>

		<div class="mt-3">
			<label class="block text-sm font-medium text-gray-700 mb-1">My Notes:</label>
			<textarea
				v-model="localNotes"
				@blur="handleUpdate"
				class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
				rows="2"
				placeholder="Add your personal notes about this word..."></textarea>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			favorite: {
				type: Object,
				required: true,
			},
		},
		data() {
			return {
				localNotes: this.favorite.notes || '',
			};
		},
		methods: {
			handleUpdate() {
				if (this.localNotes !== this.favorite.notes) {
					this.$emit('update', { id: this.favorite.id, notes: this.localNotes });
				}
			},
			handleDelete() {
				this.$emit('delete', this.favorite.id);
			},
		},
	};
</script>
