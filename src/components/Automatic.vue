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
					<span>Nacional</span>
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
				<h2>Noticias</h2>
			  <el-button @click="getLinks">Default</el-button>
		</el-col>
	</el-row>
	
</template>
<script>
	export default {
		data(){
			return{
				raw: {},

			}
			
		},
		methods:{
			getList(key){
				
				console.log(this.key);
				let data = {
					'action':'nptv_get_list',
					'cat':'nacional'
				}
				axios.post(NPTV.ajax_url, Qs.stringify(data))
		        .then(function(response){
		          console.log(response)
		          this.raw = response.data
		        }.bind(this))
		        .catch(function(error){
		          console.log(error);
		        })
			},
			handleOpen(key, keyPath){
				this.getList(key);
				console.log(key, keyPath);
			},
			handleClose(key, keyPath){
				console.log(key, keyPath);
			}
		}
	}
</script>