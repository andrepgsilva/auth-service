import { protectedActionsMiddleware } from './middlewares/protectedActionsMiddleware';

const dispatchAction = (currentAction, context) => {
  return new Promise((resolve, reject) => {
    protectedActionsMiddleware(context)
      .then(middlewareResponse => {
        resolve(currentAction());
      })
      .catch(error => {
        localStorage.setItem('user', '');
        reject();
      });
  });
};

export { dispatchAction }; 