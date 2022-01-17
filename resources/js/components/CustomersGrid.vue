<template>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="customer in customers"
                :key="customer.id"
                v-on:click="window.location.href='/customer/' + customer.id"
            >
                <td>{{ customer.first_name + " " + customer.last_name }}</td>
                <td>{{ customer.email }}</td>
                <td><button class="btn btn-primary">Edit</button></td>
                <td><button class="btn btn-primary">Delete</button></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data() {
        return {
            customers: []
        }
    },
    created() {
        fetch('/api/customer')
            .then(res => res.json())
            .then(res => this.customers = res)
            .catch(error => console.log(error));
    },
    methods: {
        
    }
}
</script>