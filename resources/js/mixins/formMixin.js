export default {
  data() {
    return {
      generalError: null,
      errors: {},
    };
  },
  methods: {
    resetErrors() {
      this.generalError = null;
      this.errors = {};
    },
    handleApiError(error) {
      if (error.response) {
        if (error.response.data.message) {
          this.generalError = error.response.data.message;
        }
        if (error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
      } else {
        console.error('Unexpected error:', error);
      }
    },
  },
};
