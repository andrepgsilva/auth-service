import moment from 'moment';

const isTokenExpired = () => {
  return moment().isAfter(localStorage.getItem('access_token_ttl'));
}

export { isTokenExpired };