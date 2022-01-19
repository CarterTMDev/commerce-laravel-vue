<template>
<div class="pt-3">
    <h3>Customers</h3>
    <p>Click on a customer to view their orders</p>
    <table class="table table-hover">
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
                        data-bs-target="#customersModal"
                        @click="editCustomer = customer;
                                $emit('modal:mode', 'edit');"
                    >
                        Edit
                    </button>
                </td>
                <td @click.stop>
                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        @click="editCustomer = customer"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#customersModal"
        @click="editCustomer = customer;
                $emit('modal:mode', 'add');"
    >
        Add customer
    </button>
    <customers-modal
        id="customersModal"
        @update:customer="updateCustomer"
        @add:customer="addCustomer"
        :customer="editCustomer"
    >
    </customers-modal>
    <warning-modal
        id="deleteModal"
        label="Delete customer"
        :message="'Are you sure you want to permanently delete '
                    + editCustomer.first_name + ' ' + editCustomer.last_name + '?'"
        @deleteModal:success="deleteCustomer"
    >
    </warning-modal>
</div>
</template>

<script>
import CustomerEditModal from './CustomersModal.vue';
import WarningModal from './WarningModal.vue';
export default {
    components: { CustomerEditModal, WarningModal },
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
        fetch(window.location.origin + '/api/customers')
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
            window.location.href='customers/' + customer.id;
        },
        addCustomer(customer) {
            this.customers.push(customer);
        },
        updateCustomer(customer) {
            this.editCustomer = customer;
            for (let i = 0; i < this.customers.length; i++) {
                if (this.customers[i].id === customer.id) {
                    this.customers[i] = customer;
                    break;
                }
            }
        },
        deleteCustomer() {
            fetch(window.location.origin + '/api/customers/' + this.editCustomer.id, {
                method: 'DELETE'
            })
            .then(res => res.ok)
            .then(success => {
                if (success) {
                    // Remove deleted customer from customers
                    for (let i = 0; i < this.customers.length; i++) {
                        if (this.customers[i].id === this.editCustomer.id) {
                            this.customers.splice(i, 1);
                            break;
                        }
                    }
                    // Set editCustomer to the empty customer object
                    this.editCustomer = this.customer;
                } else {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    }
}
</script>