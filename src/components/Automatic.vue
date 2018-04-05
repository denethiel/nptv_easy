<template>
	<el-row class="tac">
		<el-col :span="5">
			<el-menu
				default-active="nacional"
				class="el-menu-vertical"
				@select="handleOpen"
				@close="handleClose">
				<el-menu-item index="nacional">
					<i class="el-icon-sort"></i>
					<span>Nacional</span>
				</el-menu-item>
				<el-menu-item index="internacional">
					<i class="el-icon-sort"></i>
					<span>Internacional</span>
				</el-menu-item>
				<el-menu-item index="estatal">
					<i class="el-icon-sort"></i>
					<span>Estatal</span>
				</el-menu-item>
				<el-menu-item index="ayuntamiento">
					<i class="el-icon-sort"></i>
					<span>Comunicados oficiales</span>
				</el-menu-item>
				<el-menu-item index="diariodelistmo">
					<i class="el-icon-sort"></i>
					<span>Diario del Istmo</span>
				</el-menu-item>
			</el-menu>
		</el-col>
		<el-col :span="18">
				<data-table :news="news" :loading="loading" :overload="overload"></data-table>
		</el-col>
	</el-row>
	
</template>
<script>
	import DataTable from './automatic/DataTable.vue'
	export default {
		data(){
			return{
				news:[],
				loading:true,
				overload:false,
				cat:'nacional'

			}
			
		},
		// computed:{
		// 	arrayNews:function(){
		// 		var result = Object.keys(this.news).map(function(key){
		// 			return [Number(key), this.news[key]];
		// 		})
		// 		return result;
		// 	}
		// },
		methods:{
			getList(key){
				this.loading = true;
				let data = {
					'action':'nptv_get_list',
					'cat':key
				}
				axios.post(NPTV.ajax_url, Qs.stringify(data))
		        .then(function(response){
		          console.log(response)
		          this.news = response.data;
		          this.loading = false;
		          this.updateNews();
		        }.bind(this))
		        .catch(function(error){
		          console.log(error);
		          this.loading = false;
		        })
			},
			updateNews(){
				this.news.forEach(function(value, key){
					let data = {
						'action':'nptv_update_new',
						'cat':this.cat,
						'texto': value.texto,
						'titulo': value.titulo,
						'imagen': value.imagen,
						'tags':'',
					}
					console.log(this.cat);
					// axios.post(NPTV.ajax_url, Qs.stringify(data))
					// 	.then(function(response){
					// 		console.log(response)
					// 	}.bind(this))
					// 	.catch(function(error){
					// 		console.log(error);
					// 	})
				})
			},
			handleOpen(key, keyPath){
				this.cat = key;
				this.getList(key);
				console.log(key, keyPath);
			},
			handleClose(key, keyPath){
				console.log(key, keyPath);
			}
		},
		components:{
			DataTable
		},
		created:function(){
			this.getList('nacional');
		}
	}
</script>