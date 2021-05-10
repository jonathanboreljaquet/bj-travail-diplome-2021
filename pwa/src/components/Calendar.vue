<template>
<div>
        <h1>CALENDRIER</h1>
        <FullCalendar :options="calendarOptions"/>
</div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
    components:{
        FullCalendar 
    },
    name: 'About',
    data() {
        return {
        calendarOptions: {
            plugins: [ dayGridPlugin, interactionPlugin ],
            initialView: 'dayGridMonth',
            height: 'auto',
        }
        }
    }
}
</script>


<style scoped>

</style>