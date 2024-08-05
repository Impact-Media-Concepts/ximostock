<template>
    <div class="saleschannel-view">
        <span class="page-title">Verkoopkanalen</span>
        <div class="saleschannel-table">
            <div class="saleschannel-table-header">
                <div class="select-name">
                    <input class="select-all" type="checkbox">
                    <span class="orderby orderby-name">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="orderby-type">
                    <span class="orderby">Type <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="orderby-date">
                    <span class="orderby">Datem <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="create-button-wraper">
                    <button class="button-create">
                        <img class="saleschannel-icon" src="/images/saleschannel-icon-light.svg" alt="saleschannel-icon-light"> Verkoopkanaal aanmaken
                    </button>
                </div>
            </div>
            <div class="saleschannels-content">
                <div v-for="saleschannel in saleschannels['data']" class="saleschannel-item">
                    <div class="saleschannel-info">
                        <div class="select-name">
                            <input class="select-all" type="checkbox">
                            <span class="orderby orderby-name">{{saleschannel.name}}</span>
                        </div>
                        <div class="type">
                            <span>{{saleschannel.channel_type}}</span>
                        </div>
                        <div class="date">
                            <span>{{formatDate(saleschannel.created_at) }}</span>
                        </div>
                        <div class="delete-open">
                            <img class="open" src="/images/chevron-down-dark.svg" alt="chavron-down">
                            <button class="delete-button">Verwijderen</button>
                        </div>
                    </div>
                    <div class="saleschannel-form">
                        <div class="form">
                            <span>URL:</span>
                            <span>API Key:</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import '../../../scss/saleschannel/SaleschannelOverview.scss';
import { format } from 'date-fns';

export default defineComponent({
    props: {
        saleschannels:{
            type: [Array, Object],
            required: true,
        }
    },
    methods: {
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
    }
})
</script>