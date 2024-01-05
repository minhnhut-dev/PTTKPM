import React from 'react';
// here we can initialise with any value we want.
const AppContext = React.createContext({});
export const AppProvider = AppContext.Provider;
export const AppConsumer = AppContext.Consumer;
export default AppContext;
