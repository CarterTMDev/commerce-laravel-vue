<template>
<div class="pt-3">
    <h3>Order Report</h3>
    <table class="table table-striped">
        <tbody>
            <tr v-for="(item, name) in report" :key="name">
                <th>{{ name }}</th>
                <td>{{ item }}</td>
            </tr>
        </tbody>
    </table>
    <button
        class="btn btn-primary"
        @click="exportToCsv"
    >
    Export to .CSV
    </button>
</div>
</template>

<script>
export default {
    data() {
        return {
            report: {},
            order: {},
            customer: {}
        }
    },
    props: {
        orderId: Number
    },
    created() {
        // Get the order data
        fetch(window.location.origin + '/api/orders/' + this.orderId)
            .then(res => res.json())
            .then(res => {
                this.order = res;
                return res;
            })
            .then(orderRes => {
                // Get the customer data
                fetch(window.location.origin + '/api/customers/' + orderRes.customer_id)
                    .then(res => res.json())
                    .then(res => {
                        this.customer = res;
                        // Generate the report object from the order and customer data
                        this.createReport()
                    })
                    .catch(error => {
                        console.log(error);
                    })
            })
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        getFullAddress(customer) {
            let address = customer.address_1;
            address += customer.address_2 ? " " + customer.address_2 + ", " : ", ";
            address += customer.city + ", ";
            address += customer.state + " " + customer.zipcode;
            return address;
        },
        createReport() {
            this.report = {
                'Order Id': this.order.id,
                'Name': this.customer.first_name + ' ' + this.customer.last_name,
                'Address': this.getFullAddress(this.customer),
                'Total': '$' + Number(this.order.initial_cost + this.order.shipping_cost).toFixed(2)
            };
        },
        exportToCsv() {
            // Convert report to a string in CSV format
            let csvString = '';
            let csvHeaders = '';
            for (const key in this.report) {
                csvHeaders += key + ',';
                if (this.report[key].toString().includes(',')) {
                    csvString += '\"' + this.report[key] + '\",';
                } else {
                    csvString += this.report[key] + ',';
                }
            }
            csvString = csvString.substring(0, csvString.length - 1);
            csvHeaders = csvHeaders.substring(0, csvHeaders.length - 1);
            // Create the CSV
            let filename = 'order_' + this.order.id + '.csv';
            let blob = new Blob([csvHeaders + '\r\n' + csvString], { type: 'text/csv;charset=utf-8;' });
            if (navigator.msSaveBlob) { // IE 10+
                navigator.msSaveBlob(blob, filename);
            } else {
                let link = document.createElement("a");
                // Check if this browser supports the HTML5 download attribute
                if (link.download !== undefined) {
                    // Download the CSV
                    let url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        }
    }
}
</script>
