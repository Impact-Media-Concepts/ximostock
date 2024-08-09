

<template>
    <div class="archive">
        <span class="title">Archief</span>
        <div class="archive-wrapper">
            <div class="archive-table">
                <div class="table-header">
                    <div class="select-name">
                        <input class="select-all" type="checkbox">
                        <span class="orderby">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    </div>
                    <div class="type">
                        <span class="orderby">Type <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    </div>
                    <div class="date">
                        <span class="orderby">Datem <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    </div>
                </div>
                <div class="table-content">
                    <div v-for="item in items['data']" :class="{'table-item': true, 'active': isActive(item.type + item.id)}">
                        <div class="table-info"  @click="toggleActive(item.type + item.id)">
                            <div class="select-name">
                                <input type="checkbox">
                                {{ item.name }}
                            </div>
                            <div class="type">
                                {{ item.type }}
                            </div>
                            <div class="date">
                                {{ formatDate(item.deleted_at) }}
                                <img :class="{'chevron-down': true, 'active': isActive(item.type + item.id)}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button>Herstellen</button>
                            <button>Verwijderen</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="archive-filter">
                filter
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/archive/ArchiveOverview.scss';
import { format } from 'date-fns';

export default defineComponent({
    props: {
        items: {
            type: [Array, Object],
            required: true,
        }
    },
    data() {
        return {
            activeItemId: null,
        };
    },
    methods: {
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        toggleActive(id) {
            this.activeItemId = this.activeItemId === id ? null : id;
        },
        isActive(id) {
            return this.activeItemId === id;
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
