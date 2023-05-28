class RepliesController < ApplicationController
	before_action :set_reply, only: [:edit, :update, :destroy]
  
	def create
		@message = Message.find(params[:message_id])
		@reply = @message.replies.build(reply_params)
		@reply.user = current_user
	  
		if @reply.save
		  redirect_to message_path(@message), notice: "Reply was successfully created."
		else
		  redirect_to message_path(@message), alert: "Failed to create reply."
		end
	  end
	  
  
	def edit
		@message = Message.find(params[:message_id])
	end
  
	def update
	  if @reply.update(reply_params)
		redirect_to message_path(@reply.message), notice: "Reply was successfully updated."
	  else
		render 'edit', alert: 'Error updating reply.'
	  end
	end
  
	def destroy
	  @reply.destroy
	  redirect_to message_path(@reply.message), notice: "Reply was successfully deleted."
	end
  
	private
  
	def set_reply
	  @reply = Reply.find(params[:id])
	end
  
	def reply_params
	  params.require(:reply).permit(:content)
	end
  end
  