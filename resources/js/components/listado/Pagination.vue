<template>
<div class="row m-t-10 m-b-10" v-show="!filtrado">
	<div class="col-6 offset-3">
		<nav>
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
		</nav>
	</div>
</div>
</template>
<script>
export default {
	props: ['pagination', 'pagesNumber', 'filtrado'],
	methods: {
		changePage(page) {
			this.$emit('changePage', page)
		}
	}
}
</script>