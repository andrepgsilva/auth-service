import moment from 'moment';

export default function ({ $axios, redirect }) {
  $axios.onRequest(config => {
    console.log('Making request to ' + config.url)
    if (config.url == '/user') {
      // const accessTokenTTL = localStorage.getItem('access_token_ttl');

      // if (moment().isAfter(accessTokenTTL)) {
      //   console.log('Is after!!!!');
      //   return;
      // }
      // Verify if the access_token was expired
    }
  })

  // $axios.onError(error => {
  //   const code = parseInt(error.response && error.response.status)
  //   if (code === 400) {
  //     redirect('/400')
  //   }
  // })
}