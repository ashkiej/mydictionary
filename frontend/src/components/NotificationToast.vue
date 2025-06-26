<!-- components/NotificationToast.vue -->
<template>
	<div class="fixed bottom-4 right-4 z-50">
		<transition-group name="toast">
			<div
				v-for="toast in toasts"
				:key="toast.id"
				:class="['mb-2 px-4 py-2 rounded-lg shadow-lg', toastClasses(toast.type)]">
				<div class="flex items-center">
					<span>{{ toast.message }}</span>
					<button
						@click="removeToast(toast.id)"
						class="ml-4 text-white opacity-70 hover:opacity-100">
						&times;
					</button>
				</div>
			</div>
		</transition-group>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				toasts: [],
			};
		},
		created() {
			this.$eventBus.on('toast', this.addToast);
		},
		beforeDestroy() {
			this.$eventBus.$off('toast', this.addToast);
		},
		methods: {
			addToast({ message, type = 'info', timeout = 5000 }) {
				const id = Date.now();
				this.toasts.push({ id, message, type });

				if (timeout) {
					setTimeout(() => {
						this.removeToast(id);
					}, timeout);
				}
			},
			removeToast(id) {
				this.toasts = this.toasts.filter((toast) => toast.id !== id);
			},
			toastClasses(type) {
				const classes = {
					success: 'bg-green-600 text-white',
					error: 'bg-red-600 text-white',
					info: 'bg-blue-600 text-white',
					warning: 'bg-yellow-500 text-white',
				};
				return classes[type] || classes.info;
			},
		},
	};
</script>

<style>
	.toast-enter-active,
	.toast-leave-active {
		transition: all 0.3s ease;
	}
	.toast-enter,
	.toast-leave-to {
		opacity: 0;
		transform: translateX(30px);
	}
</style>
