class MessagesController < ApplicationController
	before_action :set_message, only: [:show, :edit, :update, :destroy, :create_reply]
	before_action :valid_user, only: [:new, :create, :create_reply]
  
	def index
		@messages = Message.where(parent_message_id: nil)
	end
  
	def new
	  @message = Message.new
	end
  
	def create
	  @message = current_user.messages.build(message_params)
  
	  if @message.save
		redirect_to message_path(@message), notice: "Message was successfully created."
	  else
		render :new, status: :unprocessable_entity
	  end
	end
  
	def edit
	end
  
	def update
		if @message.update(message_params)
			redirect_to message_path(@message), notice: "Message was successfully updated."
		else
			render :edit, status: :unprocessable_entity
		end
	end
  
	def destroy
	  @message.destroy
  
	  respond_to do |format|
		format.html { redirect_to messages_url, notice: "Message was successfully destroyed." }
		format.json { head :no_content }
	  end
	end
  
	def show
	  @message = Message.find(params[:id])
	  @replies = @message.replies
	  @reply = Reply.new
	end
  
	private
  
	def message_params
		params.require(:message).permit(:content)
	end

	def set_message
		@message = Message.find_by(id: params[:id])
		
		if @message.nil?
		  redirect_to messages_path, notice: "The requested message could not be found."
		end
	end
	  
  
	def valid_user
		unless current_user.role == 1
		redirect_to messages_path, notice: "Unauthorized access"
		end
	end
  
end