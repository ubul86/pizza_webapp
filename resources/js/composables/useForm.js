import { ref } from "vue";

export default function useForm() {
    const generalError = ref(null);
    const formErrors = ref({});

    const resetErrors = () => {
        generalError.value = null;
        formErrors.value = {};
    };

    const handleApiError = (error) => {
        if (error.response) {
            if (error.response.data.message) {
                generalError.value = error.response.data.message;
            }
            if (error.response.data.errors) {
                formErrors.value = error.response.data.errors;
                console.log(error.response.data.errors);
            }
        } else {
            console.error("Unexpected error:", error);
        }
    };

    return {
        generalError,
        formErrors,
        resetErrors,
        handleApiError,
    };
}
