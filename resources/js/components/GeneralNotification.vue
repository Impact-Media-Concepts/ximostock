<script setup lang="jsx">
import { defineProps, ref, watch } from 'vue';
import '../../scss/GeneralNotification.scss';

const props = defineProps({
    messages: {
        type: [Array, Object, String],
        default: () => [],
    },
    isError: {
        type: Boolean,
        default: false
    }
});

const animationKey = ref(0);  // This will be used to trigger the animation reset

const renderMessageElements = (messages) => {
    if (typeof messages === 'string') {
        return <div>{messages}</div>;
    } else if (Array.isArray(messages)) {
        return messages.map((message, index) => (
            <div key={index}>{message}</div>
        ));
    } else if (typeof messages === 'object') {
        return Object.keys(messages).map((key) => (
            <div key={key}>
                <strong>{key}:</strong>
                {Array.isArray(messages[key])
                    ? messages[key].map((msg, i) => <div key={i}>{msg}</div>)
                    : <div>{messages[key]}</div>}
            </div>
        ));
    }
    return null;
};

watch(() => props.messages, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        animationKey.value += 1;  // Increment the key to reset the animation
    }
});

const render = () => {
    return (
        <div class='general-notification' key={animationKey.value}>
            {renderMessageElements(props.messages)}
        </div>
    );
};
</script>

<template>
    <div v-if="messages">
        <component
            :class="{'succes': !isError, 'error' : isError, 'general-notification' : true}"
            :is="render"
        />
    </div>
</template>
