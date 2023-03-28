Rails.application.routes.draw do
  devise_for :users
  resources :projects
  resources :users
 
  get '/users', to: 'users#index'
  patch 'users/:id/update_role', to: 'users#update_role', as: 'update_role'
  root 'home#index'
end
