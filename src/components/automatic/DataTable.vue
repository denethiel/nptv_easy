<template>
	<el-table 
		ref="multipleTable"
		:data="news"
		style="width:100%"
		@selection-change="handleSelectionChange">
		<el-table-column type="selection" width="55">
		</el-table-column>
		<el-table-column property="Titulo" label="titulo" width="120">
		</el-table-column>
		<el-table-column label="Imagen" width="120">
			<template slot-scope="scope">
				<img :src="scope.row.imagen" style="width:100%">
			</template>
		</el-table-column>
		<el-table-column property="texto" label="Texto" width="600"></el-table-column>
	</el-table>
</template>
<script>

	export default {
		data(){
			return{
				multipleSelection:[]
			}
		},
		props:['news'],
		methods: {
			handleSelectionChange(val){
				this.multipleSelection = val;
			}
		}
	}
</script>