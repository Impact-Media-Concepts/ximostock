<template>
    <div class="activity-log">
        <span class="page-title">Activity Log</span>
        <div class="activity-table">
            <div class="table-header">
                <!-- username activity model date/time
                details -->
                <div class="orderby name">
                    <span @click="orderByLogs('name')">Gebruiker</span>
                    <div @click="orderByLogs('name')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'name'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby activity">
                    <span @click="orderByLogs('event')">Activiteit</span>
                    <div @click="orderByLogs('event')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'event'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby model">
                    <span @click="orderByLogs('subject_type')">Model</span>
                    <div @click="orderByLogs('subject_type')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'subject_type'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby date">
                    <span @click="orderByLogs('created_at')">Datem</span>
                    <div @click="orderByLogs('created_at')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'created_at'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
            </div>
            <div class="table-content">
                <div :class="{'table-item': true, 'active': activeLog == log.id}" v-for="log in logs['data']">
                    <div class="table-info" @click="toggleActiveLog(log.id)">
                        <div class="name">
                            <span>{{ LogName(log) }}</span>
                        </div>
                        <div class="activity">
                            <span>{{ log.description }}</span>
                        </div>
                        <div class="model">
                            <span>{{ logModel(log.subject_type) }}</span>
                        </div>
                        <div class="date">
                            <span>{{ formatDate(log.created_at) }}</span>
                        </div>
                        <div class="dropdown-wrapper">
                            <img :class="{'chevron-down': true, 'active' : activeLog == log.id}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                        </div>
                    </div>
                    <div class="item-content">
                        <div class="details">
                            <pre>{{ log.properties }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-footer">
                <span>
                    {{ logs.current_page }} van {{ logs.last_page }} pagina's.
                </span>
                <div class="pagination">
                    <div v-for="link in logs.links" :key="link.label">
                        <span v-if="!isNaN(link.label)"
                        @click="changePagination(link)"
                        :class="['link', { 'active-link': link.active }]">
                        <span  v-html="link.label"></span>
                    </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent, inject } from 'vue';
    import '../../../scss/activity/ActivityLog.scss';
    import { format} from 'date-fns';
    import axios from 'axios';
    import GeneralNotification from '../GeneralNotification.vue';
    export default defineComponent({
        props: {
            icons: {
                type: [Array, Object],
                required: true,
            },
            logs: {
                type: [Array, Object],
                required: true,
                default: () => [],
            },
            orderby: {
                type: String,
                required: true,
                default: 'created_at',
            },
            order: {
                type: String,
                required: true,
                default: 'desc',
            },
        },
        data(){
            return {
                activeLog: null,

            }
        },
        methods: {
            formatDate(date){
                return format(new Date(date), 'dd/MM/yyyy HH:mm:ss');
            },
            logModel(subject_type){
                return subject_type.split('\\').pop();
            },
            toggleActiveLog(id){
                if(this.activeLog === id){
                    this.activeLog = null;
                }else{
                    this.activeLog = id;
                }
            },
            LogName(log){
                if(log.causer == null){
                    return 'System';
                }else{
                    return log.causer.name;
                }
            },
            changePagination(link) {
                // Check if the link is not active (to prevent reloading the same page)
                window.location.href = link.url;
            },
            orderByLogs(column) {
                let order;
                if(this.orderby === column) {
                    order = this.order === 'asc' ? 'desc' : 'asc';
                } else {
                    order = 'asc';
                }
                const params = {
                    orderby: column,
                    order: order,
                }

                const url = this.route('activity-log.index', params);
                window.location.href = url;
            },
        },
        setup() {
            const route = inject('route'); // Injecting route helper
            return {
                route,
            };
        },
    });
</script>
