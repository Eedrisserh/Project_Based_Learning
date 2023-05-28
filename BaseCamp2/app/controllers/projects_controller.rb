class ProjectsController < ApplicationController
  before_action :authenticate_user!
  before_action :set_project, only: %i[ show edit update destroy ]
  before_action :valid_user, only: [:edit, :update, :destroy]
  include ActiveStorage::SetCurrent

  def index
    @projects = Project.all
  end

  def show
  end

  def new
    @project = current_user.projects.build
    
  end

  def edit
  end

  def create
    @project = current_user.projects.build(project_params)
  
    respond_to do |format|
      if @project.save
        format.html { redirect_to project_url(@project), notice: "Project was successfully created." }
        format.json { render :show, status: :created, location: @project }
      else
        format.html { render :new, status: :unprocessable_entity }
        format.json { render json: @project.errors, status: :unprocessable_entity }
      end
    end
  end
  

  def update
    if params[:project][:remove_attachment] == "1"
      @project.attachment.purge
    end

    if !@project.attachment.nil? && params[:project][:remove_attachment] == "0"
      respond_to do |format|
        if @project.update(update_params)
          format.html { redirect_to project_url(@project), notice: "Project was successfully updated." }
          format.json { render :show, status: :ok, location: @project }
        else
          format.html { render :edit, status: :unprocessable_entity }
          format.json { render json: @project.errors, status: :unprocessable_entity }
        end
      end
    else
      respond_to do |format|
        if @project.update(project_params)
          format.html { redirect_to project_url(@project), notice: "Project was successfully updated." }
          format.json { render :show, status: :ok, location: @project }
        else
          format.html { render :edit, status: :unprocessable_entity }
          format.json { render json: @project.errors, status: :unprocessable_entity }
        end
      end
    end
  end
  
  def destroy
    @project.destroy

    respond_to do |format|
      format.html { redirect_to projects_url, notice: "Project was successfully destroyed." }
      format.json { head :no_content }
    end
  end
  
  def valid_user
    if current_user.role == 1
      @projects = Project.all
    else
      @projects = current_user.projects
    end
  
    @project = @projects.find_by(id: params[:id])
    
    if @project.nil?
      redirect_to projects_path, notice: "Unauthorized User"
    end
  end

  private
    def set_project
      @project = Project.find(params[:id])
    end

    def project_params
      params.require(:project).permit(:project_title, :project_content, :first_name, :attachment)
    end
    
    def update_params
      params.require(:project).permit(:project_title, :project_content, :first_name)
    end
end
