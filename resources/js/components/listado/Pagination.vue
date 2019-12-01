<template>
<!-- <nav> -->
	<ul class="pagination m-b-0">
		<li v-if="pagination.current_page > 1" @click.prevent="changePage(pagination.current_page - 1)" class="page-item">
			<a href="" class="page-link">
				<span><i class="icon-back"></i></span>
			</a>
		</li>

		<li v-for="page in pagesNumber" v-bind:class="page==pagination.current_page ? 'active' : ''" class="page-item">
			<a href="#" @click.prevent="changePage(page)" class="page-link">
				{{ page }}
			</a>
		</li>

		<li v-if="pagination.current_page < pagination.last_page" @click.prevent="changePage(pagination.current_page + 1)" class="page-item">
			<a href="" class="page-link">
				<span><i class="icon-next"></i></span>
			</a>
		</li>
	</ul>
<!-- </nav> -->
</template>
<script>
export default {
	props: ['pagination', 'pagesNumber', 'is_filter'],
	methods: {
		changePage(page) {
			this.$emit('changePage', page)
		}
	}
}
</script>
<style scoped>
.pagination {
	display: inline-block;
}

.page-item {
	display: inline-block;
}
</style>