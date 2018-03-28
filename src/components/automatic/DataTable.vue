<template>
	<el-table 
		ref="multipleTable"
		:data="news"
		style="width:100%"
		v-loading="loading"
		@selection-change="handleSelectionChange">
		<el-table-column type="selection" width="55">
		</el-table-column>
		<el-table-column property="titulo" label="Titulo" width="120">
		</el-table-column>
		<el-table-column label="Imagen" width="120">
			<template slot-scope="scope">
				<img :src="scope.row.imagen" style="width:100%">
			</template>
		</el-table-column>
		<el-table-column label="Texto" width="400" class-name="truncate">
			<template slot-scope="scope">
				{{scope.row.texto}}
			</template>
		</el-table-column>
		<el-table-column label="Status" width="120">
			<template slot-scope="scope">
				<div v-if="scope.row.publish">
					<el-tag type="success">Publicado</el-tag>
				</div>
				<div v-else>
					<el-tag type="danger">Sin publicar</el-tag>
				</div>
			</template>
		</el-table-column>
	</el-table>
</template>
<script>

	export default {
		data(){
			return{
				multipleSelection:[],
				loading2: true
			}
		},
		props:['news','loading','overload'],
		methods: {
			handleSelectionChange(val){
				this.multipleSelection = val;
			}
		},
	}
</script>
<style>
	.truncate .cell {
  		white-space: nowrap;
  		overflow: hidden;
  		text-overflow: ellipsis;
	}
</style>