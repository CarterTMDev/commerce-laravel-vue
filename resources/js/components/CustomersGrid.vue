<template>
<div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="customer in customers"
                :key="customer.id"
                @click="selectCustomer(customer)"
            >
                <td>{{ customer.first_name + " " + customer.last_name }}</td>
                <td>{{ customer.email }}</td>
                <td>{{ getFullAddress(customer) }}</td>
                <td @click.stop>
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        @click="editCustomer = customer"
                    >
                        Edit
                    </button>
                </td>
                <td @click.stop>
                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <customer-edit-modal @update:customer="updateCustomer" :customer="editCustomer"></customer-edit-modal>
</div>
</template>

<script>
import CustomerEditModal from './CustomerEditModal.vue';
export default {
    components: { CustomerEditModal },
    data() {
        return {
            customers: [],
            editCustomer: this.customer
        }
    },
    props: {
        customer: Object
    },
    created() {
        fetch('/api/customer')
            .then(res => res.json())
            .then(res => this.customers = res)
            .catch(error => console.log(error));
    },
    methods: {
        getFullAddress(customer) {
            let address = customer.address_1;
            address += customer.address_2 ? " " + customer.address_2 + ", " : ", ";
            address += customer.city + ", ";
            address += customer.state + " " + customer.zipcode;
            return address;
        },
        selectCustomer(customer) {
            window.location.href='/customer/' + customer.id;
        },
        updateCustomer(customer) {
            this.editCustomer = customer;
            for (let i = 0; i < this.customers.length; i++) {
                if (this.customers[i].id === customer.id) {
                    this.customers[i] = customer;
                    break;
                }
            }
        }
    }
}
</script>