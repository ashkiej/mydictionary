<!-- components/FavoriteItem.vue -->
<template>
	<div class="bg-white rounded-lg shadow p-4 max-w-2xl mx-auto">
		<div class="flex justify-between items-start">
			<div class="flex-1">
				<div class="flex items-center gap-2">
					<h3 class="text-xl font-bold text-gray-800">{{ favorite.word }}</h3>
					<button
						@click="toggleExpanded"
						class="text-gray-500 hover:text-gray-700 transition-colors">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="h-5 w-5 transition-transform duration-200"
							:class="{ 'rotate-180': isExpanded }"
							fill="none"
							viewBox="0 0 24 24"
							stroke="currentColor">
							<path
								stroke-linecap="round"
								stroke-linejoin="round"
								stroke-width="2"
								d="M19 9l-7 7-7-7" />
						</svg>
					</button>
				</div>
				<p
					v-if="isExpanded && favorite.phonetics && favorite.phonetics.length"
					class="text-gray-600 italic flex items-center gap-2 mt-1">
					{{ favorite.phonetics[0].text }}
					<audio
						v-if="favorite.phonetics[0].audio"
						:src="favorite.phonetics[0].audio"
						controls
						class="inline-block w-32 align-middle"></audio>
				</p>
			</div>
			<button
				@click="handleDelete"
				class="text-red-600 hover:text-red-800 ml-2">
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

		<!-- Expanded content - only shown when expanded -->
		<div
			v-if="isExpanded"
			class="mt-3 space-y-4">
			<!-- Parts of Speech -->
			<div v-if="favorite.part_of_speech && favorite.part_of_speech.length">
				<h4 class="text-sm font-medium text-gray-500 mb-2">Parts of Speech:</h4>
				<div class="flex flex-wrap gap-2">
					<span
						v-for="pos in favorite.part_of_speech"
						:key="pos"
						class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
						{{ pos }}
					</span>
				</div>
			</div>

			<!-- Definitions -->
			<div>
				<h4 class="text-sm font-medium text-gray-500 mb-2">Definitions:</h4>
				<ul class="space-y-2">
					<li
						v-for="(def, index) in favorite.definitions"
						:key="index"
						class="ml-4 p-3 bg-gray-50 rounded-lg">
						<p class="text-gray-800">
							<span class="font-medium text-blue-600">{{ index + 1 }}.</span>
							<span class="italic text-gray-500 mr-2 font-medium">[{{ def.partOfSpeech }}]</span>
							{{ def.definition }}
						</p>
					</li>
				</ul>
			</div>

			<!-- Example Usage -->
			<div v-if="favorite.example_usage && favorite.example_usage.length">
				<h4 class="text-sm font-medium text-gray-500 mb-2">Example Usage:</h4>
				<ul class="space-y-1">
					<li
						v-for="(example, index) in favorite.example_usage"
						:key="index"
						class="ml-4 p-2 bg-green-50 rounded border-l-4 border-green-200">
						<p class="text-gray-700 italic">"{{ example }}"</p>
					</li>
				</ul>
			</div>

			<!-- Synonyms -->
			<div v-if="favorite.synonyms && Object.keys(favorite.synonyms).length">
				<h4 class="text-sm font-medium text-gray-500 mb-2">Synonyms:</h4>
				<div class="flex flex-wrap gap-2">
					<span
						v-for="synonym in Object.values(favorite.synonyms)"
						:key="synonym"
						class="px-2 py-1 bg-purple-100 text-purple-800 text-sm rounded">
						{{ synonym }}
					</span>
				</div>
			</div>
		</div>

		<!-- Notes section - always visible -->
		<div class="mt-3">
			<label class="block text-sm font-medium text-gray-700 mb-1">My Notes:</label>
			<textarea
				v-model="localNotes"
				class="w-full max-w-xl px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
				rows="2"
				placeholder="Add your personal notes about this word..."></textarea>
			<button
				@click="handleUpdate"
				class="mt-2 w-full max-w-xl px-3 py-2 border rounded-lg bg-blue-500 text-white hover:bg-blue-600">
				Update Notes
			</button>
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
				isExpanded: false,
			};
		},
		methods: {
			toggleExpanded() {
				this.isExpanded = !this.isExpanded;
			},
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
