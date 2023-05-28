Rails.application.routes.draw do
  devise_for :users
  resources :projects
  resources :users
  resources :messages, only: [:index, :new, :create, :show, :edit, :update, :destroy] do
    resources :replies, only: [:create, :update, :edit, :destroy] do
      delete '/', to: 'replies#destroy', on: :collection
    end
  end
  
  delete '/replies/:id', to: 'replies#destroy', as: 'destroy_reply'

  get '/users', to: 'users#index'
  patch 'users/:id/update_role', to: 'users#update_role', as: 'update_role'
  root 'home#index'
end
