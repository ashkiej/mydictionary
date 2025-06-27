<!-- components/WordCard.vue -->
<template>
	<div class="bg-white rounded-lg shadow p-6 max-w-4xl">
		<!-- Word Header -->
		<div class="flex justify-between items-start border-b border-gray-200 pb-4">
			<div>
				<h1 class="text-3xl font-bold text-gray-900">{{ wordData.word }}</h1>

				<!-- Phonetics -->
				<div
					v-if="wordData.phonetics && wordData.phonetics.length > 0"
					class="mt-2 flex flex-wrap gap-4">
					<div
						v-for="(phonetic, index) in wordData.phonetics"
						:key="index"
						class="flex items-center gap-2">
						<span class="text-lg text-gray-600 font-mono">{{ phonetic.text }}</span>
						<button
							v-if="phonetic.audio"
							@click="playAudio(phonetic.audio)"
							class="text-blue-600 hover:text-blue-800 text-sm">
							🔊
						</button>
					</div>
				</div>

				<!-- Parts of Speech Summary -->
				<div
					v-if="wordData.part_of_speech && wordData.part_of_speech.length > 0"
					class="mt-2">
					<span class="text-sm text-gray-500 italic">
						{{ wordData.part_of_speech.join(', ') }}
					</span>
				</div>
			</div>

			<!-- Save button or check icon -->
			<div>
				<transition
					name="fade-bounce"
					mode="out-in">
					<button
						v-if="!isWordFavorite"
						key="save"
						@click="$emit('save', wordData)"
						class="bg-green-100 text-green-800 px-3 py-1 rounded-lg hover:bg-green-200 flex-shrink-0 transition-transform duration-200 hover:scale-105">
						Save
					</button>
					<span
						v-else
						key="check"
						class="text-green-600 text-2xl px-3 py-1 flex items-center animate-bounce-once"
						title="Already in favorites">
						&#10003;
					</span>
				</transition>
			</div>
		</div>

		<!-- Definitions by Part of Speech -->
		<div class="mt-6">
			<div
				v-for="partOfSpeech in uniquePartsOfSpeech"
				:key="partOfSpeech"
				class="mb-6">
				<!-- Part of Speech Header -->
				<div class="flex items-baseline gap-3 mb-3">
					<h2 class="text-lg font-semibold text-gray-800 italic">{{ partOfSpeech }}</h2>
					<div class="flex-1 border-b border-gray-300"></div>
				</div>

				<!-- Definitions for this part of speech -->
				<ol class="space-y-3 ml-4">
					<li
						v-for="(definition, defIndex) in getDefinitionsForPartOfSpeech(partOfSpeech)"
						:key="defIndex"
						class="flex">
						<span class="font-medium text-gray-700 mr-3 flex-shrink-0">{{ defIndex + 1 }}.</span>
						<div class="flex-1">
							<p class="text-gray-800 leading-relaxed">{{ definition.definition }}</p>
						</div>
					</li>
				</ol>
			</div>
		</div>

		<!-- Example Usage -->
		<div
			v-if="wordData.example_usage && wordData.example_usage.length > 0"
			class="mt-6 border-t border-gray-200 pt-4">
			<h3 class="text-md font-semibold text-gray-700 mb-3">Examples</h3>
			<div class="space-y-2">
				<p
					v-for="(example, exampleIndex) in wordData.example_usage"
					:key="exampleIndex"
					class="text-gray-600 italic ml-4 relative">
					<span class="absolute -left-3 text-gray-400">"</span>
					{{ example }}
					<span class="text-gray-400">"</span>
				</p>
			</div>
		</div>

		<!-- Synonyms -->
		<div
			v-if="wordData.synonyms && wordData.synonyms.length > 0"
			class="mt-6 border-t border-gray-200 pt-4">
			<h3 class="text-md font-semibold text-gray-700 mb-3">Synonyms</h3>
			<div class="flex flex-wrap gap-2">
				<span
					v-for="(synonym, synonymIndex) in wordData.synonyms"
					:key="synonymIndex"
					class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm border border-blue-200 hover:bg-blue-100 transition-colors">
					{{ synonym }}
				</span>
			</div>
		</div>
	</div>
</template>

<script>
	import { mapGetters } from 'vuex';

	export default {
		props: {
			wordData: {
				type: Object,
				required: true,
			},
		},
		computed: {
			favorites() {
				return this.$store.state.favorites.favorites.data;
			},
			isWordFavorite() {
				const list = this.favorites || [];
				// Compare the word value with the favorites

				console.log(list);
				return list.some((favorite) => favorite.word === this.wordData.word);
			},

			uniquePartsOfSpeech() {
				if (!this.wordData.definitions) return [];

				// Get unique parts of speech from definitions, maintaining order
				const partsOfSpeech = this.wordData.definitions.map((def) => def.partOfSpeech);
				return [...new Set(partsOfSpeech)];
			},
		},
		methods: {
			getDefinitionsForPartOfSpeech(partOfSpeech) {
				if (!this.wordData.definitions) return [];

				return this.wordData.definitions.filter((def) => def.partOfSpeech === partOfSpeech);
			},

			playAudio(audioUrl) {
				const audio = new Audio(audioUrl);
				audio.play().catch((error) => {
					console.warn('Could not play audio:', error);
				});
			},
		},
	};
</script>

<style scoped>
	.fade-bounce-enter-active,
	.fade-bounce-leave-active {
		transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
	}
	.fade-bounce-enter-from,
	.fade-bounce-leave-to {
		opacity: 0;
		transform: scale(0.8) translateY(-10px);
	}
	.fade-bounce-enter-to,
	.fade-bounce-leave-from {
		opacity: 1;
		transform: scale(1) translateY(0);
	}
	@keyframes bounce-once {
		0% {
			transform: scale(1);
		}
		30% {
			transform: scale(1.2);
		}
		50% {
			transform: scale(0.95);
		}
		70% {
			transform: scale(1.05);
		}
		100% {
			transform: scale(1);
		}
	}
	.animate-bounce-once {
		animation: bounce-once 0.6s;
	}
</style>
