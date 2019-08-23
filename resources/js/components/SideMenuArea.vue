<template>
<ul class="nav nav-treeview">
    <li class="nav-item" v-for="area in areas" :key="area.id" >
    <router-link :to="'/medcheck/public/exam_area/' + area.id" class="nav-link">
        <i class="nav-icon fa fa-circle-o"></i>
        <p>
            {{ area.name }}
        </p>
        </router-link> 
    </li>
</ul>
</template>

<script>
    export default {
        props: ['area_list','role_id'],
        data() {
            return {
                areas:[]
            }
        },
        methods: {
            setAreas:function(){
                this.areas = this.area_list;
            },
            loadTableColumns: function() {
            axios.get("api/side_menu_area/" + this.role_id).then(({ data }) => {
                this.areas = data.area_list;
            });
            },            
        },
        
        mounted() {
            this.setAreas();
            console.log('SideMenuArea mounted.');   
            this.$eventHub.$on('examAreaUpdated',()=>{
                this.loadTableColumns();
            } );            
        }
    }
</script>
