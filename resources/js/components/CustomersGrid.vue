<template>
<div class="pt-3">
    <h3>Customers</h3>
    <p>Click on a customer to view their orders</p>
    <div v-if="customers.length > 0">
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
        <nav aria-label="Pages" v-if="customers.length > 0">
            <ul class="pagination justify-content-center">
                <li :class="(!pagination.prev_page_url ? 'disabled ' : '') + 'page-item'">
                    <a class="page-link" @click="retrieveCustomers(pagination.prev_page_url)" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#">
                        Page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </a>
                </li>
                <li :class="(!pagination.next_page_url ? 'disabled ' : '') + 'page-item'">
                    <a class="page-link" @click="retrieveCustomers(pagination.next_page_url)" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div v-else class="container">
        <figure class="text-center">
            <blockquote class="blockquote">
                <p>No customers found</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                Click the Add Customer button to create one
            </figcaption>
        </figure>
    </div>
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
            editCustomer: this.customer,
            pagination: {}
        }
    },
    props: {
        customer: Object
    },
    created() {
        this.retrieveCustomers();
    },
    methods: {
        getFullAddress(customer) {
            let address = customer.address_1;
            address += customer.address_2 ? " " + customer.address_2 + ", " : ", ";
            address += customer.city + ", ";
            address += customer.state + " " + customer.zipcode;
            return address;
        },
        setPagination(res) {
            this.pagination = {
                current_page: res['current_page'],
                last_page: res['last_page'],
                next_page_url: res['next_page_url'],
                prev_page_url: res['prev_page_url']
            };
        },
        retrieveCustomers(page_url) {
            page_url = page_url || window.location.origin + '/api/customers';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.customers = res['data'];
                    this.setPagination(res);
                })
                .catch(error => console.log(error));
        },
        selectCustomer(customer) {
            window.location.href='customers/' + customer.id;
        },
        addCustomer(customer) {
            this.customers.push(customer);
            this.retrieveCustomers();
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
                    // Retrieve the refreshed customers page
                    this.retrieveCustomers();
                } else {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    }
}
</script>