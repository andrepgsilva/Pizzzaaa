export default function(key) {
  const stripe = Stripe('pk_test_51HX8w0G96rNHkL2nj1Rjri30Yh9em3W6LSqJc5Y40FWM5ImEf0iA1kwka1ZAReusvKywppfGztaBUXVPj1Vm6VcE00i6bQDDqj');
  
  const elements = stripe.elements();
  const cardElement = elements.create('card');
  
  cardElement.mount('#card-element');
  
  const cardHolderName = document.getElementById('card-holder-name');
  const cardButton = document.getElementById('card-button');
  
  cardButton.addEventListener('click', async (e) => {
      const { paymentMethod, error } = await stripe.createPaymentMethod(
          'card', cardElement, {
              billing_details: { name: cardHolderName.value }
          }
      );

      if (error) {
          // Display "error.message" to the user...
          if (error.code == 'parameter_invalid_empty') {
            this.$store.commit('changeCardNotificationMessage', 'You need to fill all fields.');

            return;
          }

          this.$store.commit('changeCardNotificationMessage', error.message);
      } else {
          // The card has been verified successfully...
          const payload = { 
            paymentMethod: paymentMethod.id,
            totalPrice: this.$store.state.cart.totalPrice, 
          };

          this.$store.dispatch('processChargeClient', payload)
            .then(response => {
              this.$store.commit('changeShowPaymentModal', false);
              this.$store.commit('changeShowOrderConfirmedModal', true);

              this.$store.commit('removeAllItemsForCart', this.$store.state);
            })
            .catch(error => {
              const errorMessage = error.response.data; 
              this.$store.commit('changeCardNotificationMessage', errorMessage);        
            })

          this.$store.commit('changeCardNotificationMessage', '');
      }
  });
}
