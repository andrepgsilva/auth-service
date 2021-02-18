import { isTokenExpired } from '../tokenVerification';

const protectedActionsMiddleware = (context) => {
	return new Promise((resolve, reject) => {
     if (isTokenExpired()) {
       context.$axios.$post('/auth/refresh-token')
		     .then(response => {
					 localStorage.setItem('access_token_ttl', response.access_token_ttl);
					 localStorage.setItem('refresh_token_ttl', response.refresh_token_ttl);

				   resolve(response);
			    })
		      .catch(error => {
						reject(error);
			    });
      }
  
      resolve();
	});
}

export { protectedActionsMiddleware };