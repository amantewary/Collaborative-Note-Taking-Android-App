package com.example.amantewary.scrawl.Adapters;

import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.example.amantewary.scrawl.R;

public class ViewHolder extends RecyclerView.ViewHolder {
    TextView title;
    TextView label;
    TextView shared_by;
    View parentView;

    public ViewHolder(@NonNull View view) {
        super(view);
        this.title = view.findViewById(R.id.viewNoteTitle);
        this.label = view.findViewById(R.id.viewLabel);
        this.shared_by = view.findViewById(R.id.tv_sharedby);
        this.parentView = view;
    }
}
