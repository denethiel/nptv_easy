<template>
	<div>
		<h3>Soy el automatico wey</h3>
		<el-button type="primary" @click="getLinks">Primary</el-button>
	</div>
	
</template>
<script>
	export default {
		data(){
			return{
				raw: {}
			}
			
		},
		methods:{
			getLinks(){
				let data = {
					'action':'nptv_get_links'
				}
				axios.post(NPTV.ajax_url, Qs.stringify(data))
		        .then(function(response){
		          console.log(response)
		          this.raw = response.data
		        }.bind(this))
		        .catch(function(error){
		          console.log(error);
		        })
			}
		}
	}
</script>