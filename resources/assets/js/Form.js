import Errors from './Errors'

class Form {
    /**
     * Create a new Form instance.
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }

    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            if(field.constructor === Array) {
                this[field] = [];
                continue;
            }
            this[field] = '';
        }

        this.errors.clear();
    }

    /**
     * Send a POST request to the given URL.
     */
    post(url) {
        return this.submit('post', url);
    }

    /**
     * Send a PUT request to the given URL.
     */
    put(url) {
        return this.submit('put', url);
    }

    /**
     * Send a PATCH request to the given URL.
     */
    patch(url) {
        return this.submit('patch', url);
    }

    /**
     * Send a DELETE request to the given URL.
     */
    delete(url) {
        return this.submit('DELETE', url);
    }

    /**
     * Submit the form.
     */
    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    this.onSuccess(response.data);

                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error.response.data.errors);

                    reject(error.response.data);
                });
        });
    }

    /**
     * Handle a successful form submission.
     */
    onSuccess(data) {
        //alert(data.message); // temporary
        this.reset();

        if (data.hasOwnProperty('redirectPath')) {
            window.location = data.redirectPath;
        }
    }

    /**
     * Handle a failed form submission.
     */
    onFail(errors) {
        this.errors.record(errors);
    }
}

export default Form;
